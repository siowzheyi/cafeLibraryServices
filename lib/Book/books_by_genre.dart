import 'package:flutter/material.dart';
import '../Model/book_model.dart';
import 'book_details.dart';
import 'book_listing.dart';

class GenreSpecificBookListScreen extends StatelessWidget {
  final String genre;

  const GenreSpecificBookListScreen({Key? key, required this.genre}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // Fetch the list of books for the selected genre using the genre parameter

    return Scaffold(
      appBar: AppBar(
        title: Text('Books in $genre Genre'),
      ),
      body: FutureBuilder<List<BookModel>>(
        // Fetch the list of books for the selected genre here
        future: FetchBook().getBookList(genre: genre),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(
              child: Text('Error: ${snapshot.error}'),
            );
          } else {
            List<BookModel> results = snapshot.data!;

            return ListView.builder(
              itemCount: results.length,
              itemBuilder: (context, index) {
                var book = results[index];
                return Card(
                  child: SizedBox(
                    height: 100.0,
                    child: ListTile(
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => BookDetailsScreen(
                              name: results[index].name,
                              genre: results[index].genre,
                              picture: results[index].picture,
                              author: results[index].author,
                            ),
                          ),
                        );
                      },
                      title: Row(
                        children: [
                          Container(
                            height: 60,
                            width: 60,
                            decoration: BoxDecoration(
                              color: Colors.green,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Center(
                              child: Image.network(
                                '${book.picture}',
                                width: double.infinity,
                                height: 150.0,
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  // Handle image loading error
                                  return const Icon(Icons.error);
                                },
                              ),
                            ),
                          ),
                          SizedBox(width: 32),
                          Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                '${book.name}',
                                style: TextStyle(
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              Text(
                                '${book.author}',
                              ),
                              Text(
                                '${book.genre}',
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            );
          }
        },
      ),
    );
  }
}
