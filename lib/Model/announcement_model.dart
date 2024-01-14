class AnnouncementModel {
  String title;
  String content;
  String picture;

  // constructor
  AnnouncementModel({
    required this.title,
    required this.content,
    required this.picture
  });

  // factory method to create an announcement from a map
  factory AnnouncementModel.fromJson(Map<String, dynamic> json) {
    return AnnouncementModel(
        title: json['title'] ?? 'Unloaded',
        content: json['content'] ?? 'Unloaded',
        picture: json['picture'] ?? 'Image is not loaded'
    );
  }

  // Method to convert the picture value into a Uri
  Uri getPictureUri() {
    return Uri.parse(picture ?? '');
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