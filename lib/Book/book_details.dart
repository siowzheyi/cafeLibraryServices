import 'package:cached_network_image/cached_network_image.dart';
import 'package:cafe_library_services/Book/borrow_book.dart';
import 'package:cafe_library_services/Report/report.dart';
import 'package:flutter/material.dart';

class BookDetailsPage extends StatelessWidget {
  final String name;
  final String genre;
  final String picture;
  final String author;
  final int remainder;
  final int availability;

  // Add more properties as needed for book details

  BookDetailsPage({
    required this.name,
    required this.genre,
    required this.picture,
    required this.author,
    required this.remainder,
    required this.availability,
    // Add more constructor parameters as needed
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(name),
        actions: [
          // Add a Report button in the app bar
          IconButton(
            icon: const Icon(Icons.report),
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
            CachedNetworkImage(
              imageUrl: picture,
              placeholder: (context, url) => CircularProgressIndicator(),
              errorWidget: (context, url, error) => Icon(Icons.error),
              width: double.infinity,
              height: 1000.0,
              fit: BoxFit.cover,
            ),
            const SizedBox(height: 16.0),
            Text(
              'Title: $name',
              style: const TextStyle(fontSize: 18.0, fontWeight: FontWeight
                  .bold),
            ),
            Text(
              'Author: $author',
              style: const TextStyle(fontSize: 16.0),
            ),
            // Add more details as needed
            ElevatedButton(
              onPressed: () {
                // Navigate to the ReserveRoom when the button is pressed
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReserveBookPage(selectedBook:
                    name,),
                  ),
                );
              },
              child: const Text('Borrow'),
            ),
          ],
        ),
      ),
    );
  }
}
