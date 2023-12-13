import 'package:cafe_library_services/Book/book_details.dart';
import 'package:cafe_library_services/main.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Book/search_history.dart';

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
  final List<Book> books = [
    Book('The Great Gatsby', 'F. Scott Fitzgerald', 'assets/great_gatsby.jpg', true),
    Book('To Kill a Mockingbird', 'Harper Lee', 'assets/to_kill_a_mockingbird.jpg', true),
    Book('1984', 'George Orwell', 'assets/1984.jpg', false),
    Book('Pride and Prejudice', 'Jane Austen', 'assets/pride_and_prejudice.jpg', true),
    Book('The Catcher in the Rye', 'J.D. Salinger', 'assets/catcher_in_the_rye.jpg', false),
  ];

  List<Book> filteredBooks = [];
  List<Book> searchHistory = [];

  @override
  void initState() {
    super.initState();
    filteredBooks = List.from(books);
    //searchHistory = List.from(searchHistory);
  }

  void filterBooks(String query) {
    setState(() {
      filteredBooks = books
          .where((book) =>
      book.title.toLowerCase().contains(query.toLowerCase()) ||
          book.author.toLowerCase().contains(query.toLowerCase()))
          .toList();
      //update search history based on the user's query
    });
  }

  void addToSearchHistory(Book book) {
    setState(() {
      searchHistory.add(book);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Book Listing'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: (){
            Navigator.pop(context, MaterialPageRoute(builder: (context) => HomePage()));
          },
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
            onPressed: () {
              showSearch(
                context: context,
                delegate: BookSearchDelegate(books, addToSearchHistory),
              );
            },
          ),
        ],
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Text(
              'Our recommendations!',
              style: TextStyle(
                fontSize: 32.0,
                fontWeight: FontWeight.bold,
                fontFamily: 'Roboto',
              ),
            ),
          ),
          SizedBox(height: 16.0),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                for (var book in books)
                  Container(
                    width: 150.0,
                    margin: EdgeInsets.symmetric(horizontal: 8.0),
                    child: BookListItem(book: book),
                  ),
              ],
            ),
          ),
          SizedBox(height: 16.0,),
          SearchHistory(
            searchHistory: searchHistory,
          ),
        ],
      ),
    );
  }
}

class Book {
  final String title;
  final String author;
  final String imageUrl;
  bool isAvailable;

  Book(this.title, this.author, this.imageUrl, this.isAvailable);
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
              title: book.title,
              author: book.author,
              imageUrl: book.imageUrl,
              isAvailable: book.isAvailable,
            ),
          ),
        );
      },
      child: Card(
        margin: EdgeInsets.symmetric(horizontal: 8.0),
        child: Container(
          width: 150.0, // Adjust the width based on your preference
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Image.asset(
                book.imageUrl,
                width: double.infinity,
                height: 150.0,
                fit: BoxFit.cover,
              ),
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      book.title,
                      style: TextStyle(fontSize: 14.0, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      'by ${book.author}',
                      style: TextStyle(fontSize: 12.0, fontStyle: FontStyle.italic),
                    ),
                    SizedBox(height: 8.0),
                    Text(
                      book.isAvailable ? 'Available' : 'Checked Out',
                      style: TextStyle(
                        fontSize: 12.0,
                        color: book.isAvailable ? Colors.green : Colors.red,
                      ),
                    ),
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

class BookDetailsScreen extends StatelessWidget {
  final Book book;

  const BookDetailsScreen({Key? key, required this.book}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Book Details'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(
              book.imageUrl,
              width: 200.0,
              height: 300.0,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16.0),
            Text(
              book.title,
              style: TextStyle(fontSize: 20.0, fontWeight: FontWeight.bold),
            ),
            Text(
              'by ${book.author}',
              style: TextStyle(fontSize: 16.0, fontStyle: FontStyle.italic),
            ),
            SizedBox(height: 16.0),
            ElevatedButton(
              onPressed: () {
                // Handle book borrowing logic
                // For example, show a confirmation dialog
                showDialog(
                  context: context,
                  builder: (context) => AlertDialog(
                    title: Text('Borrow Confirmation'),
                    content: Text('Do you want to borrow ${book.title}?'),
                    actions: [
                      TextButton(
                        onPressed: () {
                          // Perform book borrowing logic here
                          Navigator.pop(context); // Close the dialog
                          // Optionally show a success message
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              content: Text('Book borrowed successfully!'),
                            ),
                          );
                        },
                        child: Text('Yes'),
                      ),
                      TextButton(
                        onPressed: () {
                          Navigator.pop(context); // Close the dialog
                        },
                        child: Text('No'),
                      ),
                    ],
                  ),
                );
              },
              child: Text('Borrow Book'),
            ),
          ],
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
        icon: Icon(Icons.clear),
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
    book.title.toLowerCase().contains(query.toLowerCase()) ||
        book.author.toLowerCase().contains(query.toLowerCase()))
        .toList();

    return ListView.builder(
      itemCount: suggestionList.length,
      itemBuilder: (context, index) {
        return ListTile(
          title: Text(suggestionList[index].title),
          onTap: () {
            // Add the selected book to the search history
            addToSearchHistory(suggestionList[index]);

            // You can navigate to the book details screen or handle the selection as needed
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => BookDetailsPage(
                  title: suggestionList[index].title,
                  author: suggestionList[index].author,
                  imageUrl: suggestionList[index].imageUrl,
                  isAvailable: suggestionList[index].isAvailable,
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