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
              height: 500.0,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16.0),
            Text(
              'Title: $name',
              style: TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
            ),
            Text(
              'Description: $description',
              style: TextStyle(fontSize: 16.0),
            ),
            Text(
              '${isAvailable ? 'Available' : 'Out of stock'}',
              style: TextStyle(
                fontSize: 16.0,
                color: isAvailable ? Colors.green : Colors.red,
              ),
            ),
            // Add more details as needed
          ],
        ),
      ),
    );
  }
}
