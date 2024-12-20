
const cartTable = document.querySelector('#cartTable tbody');
const grandTotalElement = document.getElementById('grandTotal');
let cart = JSON.parse(localStorage.getItem('cart')) || [];


function renderCart() {
  cartTable.innerHTML = '';
  let grandTotal = 0;

  cart.forEach((item, index) => {
    const subtotal = item.price * item.quantity;
    grandTotal += subtotal;

    // Create a row for each cart item
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><img src="${item.image}" alt="${item.name}" style="width: 50px; height: auto;"></td>
      <td>${item.name}</td>
      <td>$${item.price}</td>
      <td>
        <input type="number" value="${item.quantity}" min="1" data-index="${index}" class="quantity-input">
      </td>
      <td>$${subtotal.toFixed(2)}</td>
      <td><button data-index="${index}" class="remove-button">Remove</button></td>
    `;
    cartTable.appendChild(row);
  });

  // Update the grand total
  grandTotalElement.textContent = grandTotal.toFixed(2);
}

// Event listener to handle quantity change
cartTable.addEventListener('change', (e) => {
  if (e.target.classList.contains('quantity-input')) {
    const index = e.target.dataset.index;
    cart[index].quantity = parseInt(e.target.value, 10);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
  }
});

// Event listener to handle item removal
cartTable.addEventListener('click', (e) => {
  if (e.target.classList.contains('remove-button')) {
    const index = e.target.dataset.index;
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
  }
});

// Event listener to handle checkout form submission
document.getElementById('checkoutForm').addEventListener('submit', (e) => {
  e.preventDefault();

  // Retrieve customer details from the form
  const name = document.getElementById('name').value;
  const phone = document.getElementById('phone').value;
  const address = document.getElementById('address').value;
  const storeCode = document.getElementById('storeCode').value; // Retrieve store code
  const paymentMode = document.getElementById('paymentMode').value;

  // Validate store code
  if (!storeCode.trim()) {
    alert('Please enter a valid store code.');
    return;
  }

  // Create the order details object
  const orderDetails = {
    customer: { name, phone, address, storeCode, paymentMode },
    cart,
    total: parseFloat(grandTotalElement.textContent),
  };

  // Save order details to localStorage
  localStorage.setItem('orderDetails', JSON.stringify(orderDetails));

  // Notify the user and redirect to the confirmation page
  alert('Order placed successfully!');
  window.location.href = 'confirmation.html';
});

// Initial render of the cart
renderCart();
