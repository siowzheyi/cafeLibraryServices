import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:cafe_library_services/Book/borrow_book.dart';
import 'package:cafe_library_services/Report/report.dart';
import 'package:flutter/material.dart';

class BookDetailsPage extends StatelessWidget {
  final String title;
  final String author;
  final String imageUrl;
  bool isAvailable;

  // Add more properties as needed for book details

  BookDetailsPage({
    required this.title,
    required this.author,
    required this.imageUrl,
    required this.isAvailable,
    // Add more constructor parameters as needed
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(title),
        actions: [
          // Add a Report button in the app bar
          IconButton(
            icon: Icon(Icons.report),
            tooltip: 'Report this item',
            onPressed: () {
              // Navigate to the ReportPage when the button is pressed
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ReportPage(),
                ),
              );
            },
          ),
        ],
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
              'Title: $title',
              style: TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
            ),
            Text(
              'Author: $author',
              style: TextStyle(fontSize: 16.0),
            ),
            Text(
              '${isAvailable ? 'Available' : 'Checked out'}',
              style: TextStyle(
                fontSize: 16.0,
                color: isAvailable ? Colors.green : Colors.red,
              ),
            ),
            // Add more details as needed
            if (isAvailable)
              ElevatedButton(
                onPressed: () {
                  // Navigate to the ReserveRoom when the button is pressed
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => ReserveBookPage(selectedBook: title,),
                    ),
                  );
                },
                child: Text('Borrow'),
              ),
          ],
        ),
      ),
    );
  }
}
