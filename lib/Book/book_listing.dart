import 'package:cafe_library_services/Book/book_details.dart';
import 'package:cafe_library_services/Book/search_book.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../Controller/connection.dart';
import '../Model/book_model.dart';
import '../Welcome/select_library.dart';
import 'books_by_genre.dart';

void main(){
  runApp(BookListing());
}

class BookListing extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: FutureBuilder<String>(
        future: getLibraryIdFromSharedPreferences(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.done) {
            if (snapshot.hasError) {
              // Handle error
              return Scaffold(
                body: Center(
                  child: Text('Error: ${snapshot.error}'),
                ),
              );
            } else {
              String libraryId = snapshot.data ?? '';
              return BookListScreen(libraryId: libraryId);
            }
          } else {
            // While waiting for the Future to complete, show a loading indicator
            return Scaffold(
              body: Center(
                child: CircularProgressIndicator(),
              ),
            );
          }
        },
      ),
    );
  }
}

class BookListScreen extends StatefulWidget {
  final String libraryId;
  final Map<String, String>? headers;

  const BookListScreen({Key? key, required this.libraryId, this.headers})
      : super(key: key);

  @override
  _BookListScreenState createState() => _BookListScreenState();
}

class FetchBook {
  late List<BookModel> books;

  Future<List<BookModel>> getBookList({String? genre}) async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();
      books = [];

      var url = Uri.parse('${API.book}?library_id=$libraryId${genre != null ? '&genre=$genre' : ''}');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer ${token}"
      };

      var response = await http.get(
        url,
        headers: header,
      );

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          // Check if 'aaData' is a List
          if (result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<BookModel> books = [];

            // Iterate through the 'aaData' list
            for (var aaData in aaDataList) {
              // Check if 'books' is a List
              if (aaData['books'] is List) {
                List<dynamic> booksList = aaData['books'];

                // Iterate through the 'books' list
                for (var bookData in booksList) {
                  // Create a BookModel instance from each book data and add it to the list
                  books.add(BookModel.fromJson(bookData));
                }
              }
            }

            return books;
          } else {
            print('Error: "aaData" is not a List');
            return [];
          }
        } catch (error) {
          print('Error decoding JSON: $error');
          return [];
        }
      }

      print('Request URL: $url');
      print('Request Headers: $header');
      print(response.statusCode);
      print(response.body);
      return [];
    } catch (error) {
      print('Error fetching books: $error');
      return [];
    }
  }

  Future<List<String>> getGenreList({String? search}) async {
    try {
      final String libraryId = await getLibraryIdFromSharedPreferences();
      final String? token = await getToken();

      // Include the search parameter only if it's provided
      var url = Uri.parse('${API.book}?library_id=$libraryId${search != null ? '&search=$search' : ''}');
      var header = {
        "Content-Type": "application/json",
        "Authorization": "Bearer $token"
      };

      var response = await http.get(
        url,
        headers: header,
      );

      if (response.statusCode == 200) {
        try {
          Map<String, dynamic> result = jsonDecode(response.body);

          // Check if 'aaData' is a List
          if (result['data']['aaData'] is List) {
            List<dynamic> aaDataList = result['data']['aaData'];
            List<String> genres = [];

            // Iterate through the 'aaData' list
            for (var aaData in aaDataList) {
              // Check if 'genre' is available
              if (aaData['genre'] != null) {
                genres.add(aaData['genre']);
              }
            }

            return genres;
          } else {
            print('Error: "aaData" is not a List');
            return [];
          }
        } catch (error) {
          print('Error decoding JSON: $error');
          return [];
        }
      }

      print('Request URL: $url');
      print('Request Headers: $header');
      print(response.statusCode);
      print(response.body);
      return [];
    } catch (error) {
      print('Error fetching genres: $error');
      return [];
    }
  }
}

class _BookListScreenState extends State<BookListScreen> {

  late Future<List<BookModel>> bookList;
  late Future<List<String>> genreList;

  @override
  void initState() {
    fetchData();
  }

  Future<void> fetchData() async {
    bookList = FetchBook().getBookList();
    genreList = FetchBook().getGenreList();
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          title: Text('Book Listing'),
          leading: IconButton(
            icon: const Icon(Icons.arrow_back),
            onPressed: () {
              Navigator.pushReplacement(
                context,
                MaterialPageRoute(
                  builder: (context) => HomePage(libraryId: ''),
                ),
              );
            },
          ),
          actions: [
            IconButton(
              icon: const Icon(Icons.search),
              onPressed: () {
                showSearch(
                  context: context,
                  delegate: SearchBook(),
                );
              },
            ),
          ],
        ),
        body: Column(
          children: [
            FutureBuilder<List<String>>(
              future: genreList,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return CircularProgressIndicator();
                } else if (snapshot.hasError) {
                  return Center(
                    child: Text('Error: ${snapshot.error}'),
                  );
                } else {
                  List<String> genres = snapshot.data!;
                  return Row(
                    children: genres
                        .map(
                          (genre) => Row(
                            children: [
                              ElevatedButton(
                        onPressed: () {
                              print('Pressed $genre');
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => GenreSpecificBookListScreen(genre: genre),
                                ),
                              );
                        },
                        child: Text(genre),
                      ),
                              SizedBox(width: 16.0),
                            ],
                          ),
                    )
                        .toList(),
                  );
                }
              },
            ),
            Expanded(
              child: FutureBuilder<List<BookModel>>(
                future: bookList,
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    return Center(
                      child: Text('Error: ${snapshot.error}'),
                    );
                  } else {
                    List<BookModel> results = snapshot.data!;
                    results.shuffle();
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
                                      id: results[index].id,
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
            ),
          ],
        ),
      ),
    );
  }

  @override
  void dispose() {
    // Clean up resources, cancel timers, etc.
    super.dispose();
  }
}