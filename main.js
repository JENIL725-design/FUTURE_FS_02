document.addEventListener('DOMContentLoaded', function() {

    // 1. INITIALIZE CART BADGE
    const badgeElement = document.getElementById('cart-count');
    const initialCount = badgeElement ? badgeElement.innerText : 0;
    
    if (typeof CartStore !== 'undefined') {
        CartStore.init(initialCount);
        
        CartStore.subscribe((state) => {
            if (badgeElement) {
                badgeElement.innerText = state.count;
                badgeElement.style.transform = "scale(1.5)";
                setTimeout(() => badgeElement.style.transform = "scale(1)", 200);
            }
        });
    }

    // 2. SINGLE EVENT LISTENER FOR ALL CLICKS
    document.body.addEventListener("click", async function (e) {
        
        // --- ADD TO CART BUTTON ---
        const addBtn = e.target.closest(".add-btn");
        if (addBtn) {
            e.preventDefault();
            const id = addBtn.dataset.id;
            const name = addBtn.dataset.name;
            const price = addBtn.dataset.price;
            
            const result = await CartStore.updateCart("add", id, name, price);
            if (result && result.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: result.message, 
                    toast: true, 
                    position: 'top-end', 
                    timer: 2000, 
                    showConfirmButton: false 
                });
            }
        }

        // --- REMOVE FROM CART BUTTON ---
        const removeBtn = e.target.closest(".remove-btn");
        if (removeBtn) {
            e.preventDefault();
            const id = removeBtn.dataset.id;

            Swal.fire({
                title: "Remove item?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove it!",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await CartStore.updateCart("remove", id);
                    if (res.success) {
                        location.reload(); 
                    }
                }
            });
        }

        // --- QUANTITY ADJUST BUTTONS (+ / -) ---
        const qtyBtn = e.target.closest(".qty-btn");
        if (qtyBtn) {
            e.preventDefault();
            const id = qtyBtn.getAttribute("data-id");
            const change = parseInt(qtyBtn.getAttribute("data-change"));
            const qtyElement = document.getElementById(`qty-${id}`);
            
            if (qtyElement) {
                let currentQty = parseInt(qtyElement.innerText);
                let newQty = currentQty + change;

                const result = await CartStore.updateQty(id, newQty);
                if (result.success) {
                    location.reload(); 
                }
            }
        }
    });
});