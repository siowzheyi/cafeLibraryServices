import 'package:cafe_library_services/Book/book_listing.dart';
import 'package:flutter/material.dart';
import '../Model/book_model.dart';

class SearchBook extends SearchDelegate {
  @override
  List<Widget>? buildActions(BuildContext context) {
    return [
      IconButton(
        onPressed: () {
          query = "";
        },
        icon: Icon(Icons.close),
      )
    ];
  }

  @override
  Widget? buildLeading(BuildContext context) {
    return IconButton(
      onPressed: () {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => BookListing(),
          ),
        );
      },
      icon: Icon(Icons.arrow_back),
    );
  }

  FetchBook bookList = FetchBook();

  @override
  Widget buildResults(BuildContext context) {
    return Container(
      child: FutureBuilder<List<BookModel>>(
        future: bookList.getBookList(genre: query),
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

  @override
  Widget buildSuggestions(BuildContext context) {
    return Center(
      child: Text('Search books'),
    );
  }
}