import 'package:cafe_library_services/Beverage/add_to_cart.dart';
import 'package:flutter/material.dart';

class BeverageDetailsPage extends StatelessWidget {
  final String name;
  final String price;
  final String description;
  final String imageUrl;
  bool isAvailable;
  // Add more properties as needed for beverage details

  BeverageDetailsPage({
    required this.name,
    required this.price,
    required this.description,
    required this.imageUrl,
    required this.isAvailable,
    // Add more constructor parameters as needed
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(name),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(
              imageUrl,
              width: double.infinity,
              height: 900.0,
              fit: BoxFit.cover,
            ),
            const SizedBox(height: 16.0),
            Text(
              'Title: $name',
              style: const TextStyle(fontSize: 18.0, fontWeight:
              FontWeight.bold),
            ),
            Text(
              'Description: $description',
              style: const TextStyle(fontSize: 16.0),
            ),
            Text(
              isAvailable ? 'Available' : 'Out of stock',
              style: TextStyle(
                fontSize: 16.0,
                color: isAvailable ? Colors.green : Colors.red,
              ),
            ),
            // Add more details as needed
            if (isAvailable)
              ElevatedButton(
                onPressed: () {
                  // Navigate to the order when the button is pressed
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => BeverageOrderPage(),
                    ),
                  );
                },
                child: const Text('Order'),
              ),
          ],
        ),
      ),
    );
  }
}
