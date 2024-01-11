class BookModel {
  String name;
  String author;
  String picture;

  // constructor
  BookModel({
    required this.name,
    required this.author,
    required this.picture
  });

  // factory method to create a book from a map
  factory BookModel.fromJson(Map<String, dynamic> map) {
    return BookModel(
        name: map['name'] ?? '',
        author: map['author_name'] ?? '',
        picture: map['picture'] ?? ''
    );
  }

  // convert the book instance to a map
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'author_name': author,
      'picture': picture,
    };
  }
}