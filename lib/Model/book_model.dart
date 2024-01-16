class BookModel {
  int id;
  String name;
  String author;
  String picture;
  String genre;

  // constructor
  BookModel({
    required this.id,
    required this.name,
    required this.author,
    required this.picture,
    required this.genre
  });

  // factory method to create a book from a map
  factory BookModel.fromJson(Map<String, dynamic> json) {
    return BookModel(
        id: json['id'] ?? '',
        name: json['name'] ?? '',
        author: json['author_name'] ?? '',
        picture: json['picture'] ?? '',
        genre: json['genre'] ?? ''
    );
  }

  // convert the book instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'author_name': author,
      'picture': picture,
      'genre': genre
    };
  }
}