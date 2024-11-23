<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
</head>
<body>
    <p>this is books tab</p>
    <h1>Product List</h1>

    <ul id="product-list"></ul>


    <script>
        fetch('get_books.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                let productList = document.getElementById('product-list');

                data.forEach(product => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${product.name} - $${product.price} - ${product.description}`;
                    productList.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error fetching products:', error));

    </script>
</body>
</html>
