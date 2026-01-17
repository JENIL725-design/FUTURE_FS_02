document.addEventListener('DOMContentLoaded', function() {

    const badgeElement = document.getElementById('cart-count');
    const initialCount = badgeElement ? badgeElement.innerText : 0;
    
    if (typeof CartStore !== 'undefined') {
        CartStore.init(initialCount);
        
        CartStore.subscribe((state) => {
            if (badgeElement) {
                console.log("Updating badge to:", state.count); 
                badgeElement.innerText = state.count;
                
                badgeElement.style.transform = "scale(1.5)";
                setTimeout(() => badgeElement.style.transform = "scale(1)", 200);
            }
        });
    }

  document.body.addEventListener("click", async function (e) {
    const btn = e.target.closest(".add-btn");

    if (btn) {
      e.preventDefault();

      const id = btn.getAttribute("data-id");
      const name = btn.getAttribute("data-name");
      const price = btn.getAttribute("data-price");
      const result = await CartStore.updateCart("add", id, name, price);

      if (result && result.success) {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
        });
        Toast.fire({ icon: "success", title: result.message });
      }
    }
  });

  document.body.addEventListener("click", function (e) {
    const btn = e.target.closest(".remove-btn");

    if (btn) {
      e.preventDefault();

      const id = btn.getAttribute("data-id");
      const row = document.getElementById("row-" + id);

      Swal.fire({
        title: "Are you sure?",
        text: "Remove this item?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove it!",
      }).then(async (result) => {
        if (result.isConfirmed) {
          const res = await CartStore.updateCart("remove", id);

          if (res.success) {
            if (row) {
              row.style.transition = "all 0.5s";
              row.style.opacity = "0";
              setTimeout(() => row.remove(), 500);
            }

            Swal.fire({
              title: "Removed!",
              text: "Item removed.",
              icon: "success",
              timer: 1000,
              showConfirmButton: false,
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
