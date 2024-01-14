class BookModel {
  String name;
  String author;
  String picture;
  String genre;

  // constructor
  BookModel({
    required this.name,
    required this.author,
    required this.picture,
    required this.genre
  });

  // factory method to create a book from a map
  factory BookModel.fromJson(Map<String, dynamic> map) {
    return BookModel(
        name: map['name'] ?? '',
        author: map['author_name'] ?? '',
        picture: map['picture'] ?? '',
        genre: map['genre'] ?? ''
    );
  }

  // convert the book instance to a map
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'author_name': author,
      'picture': picture,
      'genre': genre
    };
  }
}