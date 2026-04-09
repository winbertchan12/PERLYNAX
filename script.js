fetch('products.php')
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('product-list');
    data.forEach(product => {
      container.innerHTML += `
        <div class="product">
          <h3>${product.name}</h3>
          <p>$${product.price}</p>
          <button>Add to Cart</button>
        </div>
      `;
    });
  });