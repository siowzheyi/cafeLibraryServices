class AnnouncementModel {
  int id;
  String title;
  String content;
  String picture;

  // constructor
  AnnouncementModel({
    required this.id,
    required this.title,
    required this.content,
    required this.picture
  });

  // factory method to create an announcement from a map
  factory AnnouncementModel.fromJson(Map<String, dynamic> json) {
    return AnnouncementModel(
        id: json['id'] ?? '',
        title: json['title'] ?? '',
        content: json['content'] ?? '',
        picture: json['picture'] ?? ''
    );
  }

  // convert the announcement instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'content': content,
      'picture': picture,
    };
  }
}