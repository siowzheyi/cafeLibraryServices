import 'package:cached_network_image/cached_network_image.dart';
import 'package:cafe_library_services/Book/book_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../Controller/connection.dart';
import 'dart:convert';

void main(){
  runApp(BookListing());
}

class BookListing extends StatelessWidget {
  const BookListing({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Book Listing',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: BookListScreen(),
    );
  }
}

class BookListScreen extends StatefulWidget {
  @override
  _BookListScreenState createState() => _BookListScreenState();
}

class _BookListScreenState extends State<BookListScreen> {

  List<Book> books = [];

  Future<void> getBooks() async {
    try {
      var response = await http.get(Uri.parse(API.book));
      if (response.statusCode == 200) {
        List<dynamic> decodedData = jsonDecode(response.body);

        setState(() {
          books = decodedData.map((data) => Book(
            data['name'] ?? '',
            data['genre'] ?? '',
            data['picture'] ?? '',
            data['author_name'] ?? '',
            int.tryParse(data['remainder_count'] ?? '') ?? 0,
            int.tryParse(data['availability'] ?? '') ?? 1,
          )).toList();
        });

        print(books);
      }
    } catch (ex) {
      print("Error :: " + ex.toString());
    }
  }

  List<Book> filteredBooks = [];
  List<Book> searchHistory = [];

  @override
  void initState() {
    getBooks();
    filteredBooks = List.from(books);
    super.initState();
  }

  List<String> getGenres() {
    Set<String> genres = {};
    for (var book in books) {
      genres.add(book.genre);
    }
    return genres.toList();
  }

  void filterBooks(String query) {
    setState(() {
      filteredBooks = books
          .where((book) =>
      book.name.toLowerCase().contains(query.toLowerCase()))
          .toList();
      //update search history based on the user's query
    });
  }

  void addToSearchHistory(Book book) {
    setState(() {
      //searchHistory.add(book);
    });
  }

  void filterBooksByGenre(String genre) {
    setState(() {
      filteredBooks = books.where((book) => book.genre == genre).toList();
      if (genre.isNotEmpty) {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => GenreBooksScreen(
              genre: genre,
              books: filteredBooks,
            ),
          ),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Book Listing'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: (){
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => HomePage()));
          },
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              showSearch(
                context: context,
                delegate: BookSearchDelegate(books, addToSearchHistory),
              );
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 16.0),
            SingleChildScrollView(
              scrollDirection: Axis.vertical,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Text('Search book by genre:',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 32.0,
                  ),),
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 16.0),
                    child: GenreSelectionWidget(filterBooksByGenre),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 16.0),
                    child: ListView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      itemCount: filteredBooks.length,
                      itemBuilder: (context, index) {
                        return BookListItem(book: filteredBooks[index]);
                      },
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16.0,),
          ],
        ),
      )
    );
  }
}

class GenreBooksScreen extends StatelessWidget {
  final String genre;
  final List<Book> books;

  const GenreBooksScreen({Key? key, required this.genre, required this.books})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(genre),
      ),
      body: GridView.builder(
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 4,
          crossAxisSpacing: 8.0,
          mainAxisSpacing: 8.0,
        ),
        itemCount: books.length,
        itemBuilder: (context, index) {
          return BookListItem(book: books[index]);
        },
      ),
    );
  }
}

class GenreSelectionWidget extends StatelessWidget {
  final Function(String) onGenreSelected;

  const GenreSelectionWidget(this.onGenreSelected);

  @override
  Widget build(BuildContext context) {
    // Use the context to obtain the current state instance
    final _BookListScreenState? state = context.findAncestorStateOfType<
        _BookListScreenState>();

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: [
          for (var genre in state!.getGenres())
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 8.0),
              child: ElevatedButton(
                onPressed: () {
                  onGenreSelected(genre);
                },
                child: Text(genre),
              ),
            ),
        ],
      ),
    );
  }
}

class Book {
  final String name;
  final String genre;
  final String picture;
  final String author;
  final int remainder;
  final int availability;

  Book(this.name, this.genre, this.picture, this.author, this.remainder, this.availability);
}

class BookListItem extends StatelessWidget {
  final Book book;

  const BookListItem({Key? key, required this.book}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => BookDetailsPage(
              name: book.name,
              genre: book.genre,
              picture: book.picture,
              author: book.author,
              remainder: book.remainder,
              availability: book.availability,
            ),
          ),
        );
      },
      child: Card(
        margin: const EdgeInsets.symmetric(horizontal: 8.0),
        clipBehavior: Clip.antiAlias, // Ensure that Card clips its children
        child: SizedBox(
          width: 150.0,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Center(
                  child: CachedNetworkImage(
                    imageUrl: book.picture,
                    placeholder: (context, url) => CircularProgressIndicator(),
                    errorWidget: (context, url, error) => Text('Image is not loaded'),
                    fit: BoxFit.cover,
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      book.name,
                      style: const TextStyle(fontSize: 14.0, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      'by ${book.author}',
                      style: const TextStyle(fontSize: 12.0, fontStyle: FontStyle.italic),
                    ),
                    const SizedBox(height: 8.0),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class BookSearchDelegate extends SearchDelegate<String> {
  final List<Book> books;
  final Function(Book) addToSearchHistory;

  BookSearchDelegate(this.books, this.addToSearchHistory);

  @override
  List<Widget> buildActions(BuildContext context) {
    return [
      IconButton(
        icon: const Icon(Icons.clear),
        onPressed: () {
          query = '';
        },
      ),
    ];
  }

  @override
  Widget buildLeading(BuildContext context) {
    return IconButton(
      icon: AnimatedIcon(
        icon: AnimatedIcons.menu_arrow,
        progress: transitionAnimation,
      ),
      onPressed: () {
        close(context, '');
      },
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    return buildSuggestions(context);
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    final suggestionList = query.isEmpty
        ? books
        : books
        .where((book) =>
    book.name.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].name),
          onTap: () {
            // Add the selected book to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the book details screen or handle the
            // selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => BookDetailsPage(
                  name: suggestionList[index].name,
                  genre: suggestionList[index].genre,
                  picture: suggestionList[index].picture,
                  author: suggestionList[index].author,
                  remainder: suggestionList[index].remainder,
                  availability: suggestionList[index].availability,
                  // Pass more details as needed
                ),
              ),
            );
          },
        );
      },
    );
  }
}
