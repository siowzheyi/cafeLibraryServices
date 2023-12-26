import 'package:cafe_library_services/Book/book_details.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

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
    Book('The Great Gatsby', 'F. Scott Fitzgerald', 'assets/great_gatsby.jpg', true, 'Classic'),
    Book('To Kill a Mockingbird', 'Harper Lee', 'assets/to_kill_a_mockingbird.jpg', true, 'Classic'),
    Book('1984', 'George Orwell', 'assets/1984.jpg', false, 'Dystopian'),
    Book('Pride and Prejudice', 'Jane Austen', 'assets/pride_and_prejudice.jpg', true, 'Classic'),
    Book('The Catcher in the Rye', 'J.D. Salinger', 'assets/catcher_in_the_rye.jpg', false, 'Fiction'),
    Book('Makanan Warisan Malaysia', 'Kalsom Taib', 'assets/makanan_warisan_malaysia.jpg', true, 'Cooking'),
    Book('Palestin - Kemenangan Yang Dekat', 'Karya Bestari', 'assets/palestin.jpg', false, 'History'),
    Book('Brain Teasers', 'Lonely Planet K', 'assets/brain_teasers.jpg', true, 'Puzzle'),
    Book('My ABC', 'Brown Watson', 'assets/abc.jpg', false, 'Children'),
    Book('The Detective Dog', 'Macmillan UK', 'assets/dog.jpg', true, 'Children'),
    Book('Sparring Partners', 'John Grisham', 'assets/sparring_partners.jpg', false, 'Fiction'),
    Book('Hades', 'Aishah Zainal', 'assets/hades.jpg', true, 'Thriller'),
  ];

  List<Book> filteredBooks = [];
  List<Book> searchHistory = [];

  @override
  void initState() {
    super.initState();
    filteredBooks = List.from(books);
    //searchHistory = List.from(searchHistory);
  }

  List<String> getGenres() {
    Set<String> genres = Set();
    for (var book in books) {
      genres.add(book.genre);
    }
    return genres.toList();
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
        title: Text('Book Listing'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: (){
            Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => HomePage()));
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
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(height: 16.0),
            SingleChildScrollView(
              scrollDirection: Axis.vertical,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Container(
                    padding: EdgeInsets.symmetric(vertical: 16.0),
                    child: GenreSelectionWidget(filterBooksByGenre),
                  ),
                  Container(
                    padding: EdgeInsets.symmetric(vertical: 16.0),
                    child: ListView.builder(
                      shrinkWrap: true,
                      physics: NeverScrollableScrollPhysics(),
                      itemCount: filteredBooks.length,
                      itemBuilder: (context, index) {
                        return BookListItem(book: filteredBooks[index]);
                      },
                    ),
                  ),
                ],
              ),
            ),
            SizedBox(height: 16.0,),
          ],
        ),
      )
    );
  }
}

class GenreBooksScreen extends StatelessWidget {
  final String genre;
  final List<Book> books;

  const GenreBooksScreen({Key? key, required this.genre, required this.books}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(genre),
      ),
      body: GridView.builder(
        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
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
    final _BookListScreenState? state = context.findAncestorStateOfType<_BookListScreenState>();

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: [
          for (var genre in state!.getGenres())
            Padding(
              padding: EdgeInsets.symmetric(horizontal: 8.0),
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
  final String title;
  final String author;
  final String imageUrl;
  bool isAvailable;
  final String genre;

  Book(this.title, this.author, this.imageUrl, this.isAvailable, this.genre);
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
          width: 150.0,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Image.asset(
                book.imageUrl,
                width: double.infinity,
                height: 200.0,
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