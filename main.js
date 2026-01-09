document.addEventListener('DOMContentLoaded', function() {

    // Helper function to update the badge UI
    function updateCartBadge(count) {
        const badge = document.querySelector('.badge');
        if (badge) {
            badge.innerText = count;
            
            // Optional: Add a little "pop" animation when number changes
            badge.style.transform = "scale(1.5)";
            setTimeout(() => badge.style.transform = "scale(1)", 200);
        }
    }

    // =========================================
    // 1. ADD TO CART (UPDATED TO USE STORE)
    // =========================================
    document.body.addEventListener('click', async function(e) {
        // Use .closest to handle clicks on the icon inside the button
        const btn = e.target.closest('.add-btn');

        if (btn) {
            e.preventDefault();
            
            // 1. Gather data
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const price = btn.getAttribute('data-price');

            // 2. Use the Store to handle the logic
            // (This automatically updates the count and notifies subscribers)
            const result = await CartStore.updateCart('add', id, name, price);

            // 3. Show Success Message
            if (result && result.success) {
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, 
                    timer: 2000, timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: result.message });
            }
        }
    });

    // =========================================
    // 4. HANDLE REMOVE FROM CART (UPDATED: Delegation)
    // =========================================
    // We attach the listener to the BODY, just like the Add Button.
    document.body.addEventListener('click', function(e) {
        
        // Check if we clicked a Remove Button
        const btn = e.target.closest('.remove-btn');

        if (btn) {
            e.preventDefault(); 
            
            const id = btn.getAttribute('data-id');
            const row = document.getElementById('row-' + id);

            Swal.fire({
                title: 'Are you sure?', text: "Remove this item?", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    
                    const res = await CartStore.updateCart('remove', id);

                    if (res.success) {
                        if (row) {
                            // Fancy fade out animation before removing
                            row.style.transition = "all 0.5s";
                            row.style.opacity = "0";
                            setTimeout(() => row.remove(), 500);
                        }
                        
                        Swal.fire({
                            title: 'Removed!', text: 'Item removed.', icon: 'success',
                            timer: 1000, showConfirmButton: false
                        });

                        if (CartStore.state.count === 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }
                }
            });
        }
    });
});