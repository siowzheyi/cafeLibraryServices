import 'package:cached_network_image/cached_network_image.dart';
import 'package:cafe_library_services/Book/borrow_book.dart';
import 'package:cafe_library_services/Report/book_report.dart';
import 'package:flutter/material.dart';

class BookDetailsScreen extends StatelessWidget {
  final int id;
  final String name;
  final String genre;
  final String picture;
  final String author;

  BookDetailsScreen({
    required this.id,
    required this.name,
    required this.genre,
    required this.picture,
    required this.author,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(name),
        actions: [
          IconButton(
            icon: const Icon(Icons.report),
            tooltip: 'Report this book',
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => BookReportPage(bookId: ''),
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
              placeholder: (context, url) => const CircularProgressIndicator(),
              errorWidget: (context, url, error) => const Icon(Icons.error),
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
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReserveBookPage(selectedBook:
                    name,)
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
