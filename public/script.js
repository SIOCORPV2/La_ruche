// Fetch marker data from the server (use an appropriate AJAX library or fetch API)
// Example using fetch API:
fetch('/js/conn.php')
    .then(response => response.json())
    .then(data => {
        console.log('Marker Data:', data);

        // Get the existing <g> element
        const existingGroup = document.getElementById('Map').querySelector('g');

        // Loop through the marker data and create SVG elements (modify as needed)
        data.forEach(marker => {
            // Create an <a> element for the marker
            const anchorElement = document.createElementNS("http://www.w3.org/2000/svg", 'a');
            anchorElement.setAttribute('href', marker.url); // Set the URL for the marker

            // Create an <image> element for the pin
            const pinElement = document.createElementNS("http://www.w3.org/2000/svg", 'image');
            pinElement.setAttribute('x', marker.x_coord + 265 ); // Adjust the position as needed
            pinElement.setAttribute('y', marker.y_coord + 32 ); // Adjust the position as needed
            pinElement.setAttribute('width', 10); // Adjust the width as needed
            pinElement.setAttribute('height', 10); // Adjust the height as needed
            pinElement.setAttribute('href', '/img/pin.png'); // Set the path to your pin image

            // Add more attributes or event listeners as needed to pinElement

            // Append the <image> element to the <a> element
            anchorElement.appendChild(pinElement);

            // Append the <a> element to the existing <g> element
            existingGroup.appendChild(anchorElement);
        });
    })
    .catch(error => console.error('Error fetching marker data:', error));

