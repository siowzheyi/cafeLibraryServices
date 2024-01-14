class BookingModel {
  String title;
  String content;
  String picture;

  // constructor
  BookingModel({
    required this.title,
    required this.content,
    required this.picture
  });

  // factory method to create an announcement from a map
  factory BookingModel.fromJson(Map<String, dynamic> json) {
    return BookingModel(
        title: json['title'] ?? 'Unloaded',
        content: json['content'] ?? 'Unloaded',
        picture: json['picture'] ?? 'Image is not loaded'
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'title': title,
      'content': content,
      'picture': picture,
    };
  }
}