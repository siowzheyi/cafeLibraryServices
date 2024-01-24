class ReportModel {
  int id;
  String status;
  int itemId;
  String itemName;
  String itemPicture;
  String libraryName;
  int libraryId;
  String name;
  String description;
  String picture;
  String createdAt;

  // Constructor
  ReportModel({
    required this.id,
    required this.status,
    required this.itemId,
    required this.itemName,
    required this.itemPicture,
    required this.libraryName,
    required this.libraryId,
    required this.name,
    required this.description,
    required this.picture,
    required this.createdAt,
  });

  // Named constructor to create an instance from JSON
  factory ReportModel.fromJson(Map<String, dynamic> json) {
    return ReportModel(
      id: json['id'] ?? 0,
      status: json['status'] ?? '',
      itemId: json['item_id'] ?? 0,
      itemName: json['item_name'] ?? '',
      itemPicture: json['item_picture'] ?? '',
      libraryName: json['library_name'] ?? '',
      libraryId: json['library_id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      picture: json['picture'] ?? '',
      createdAt: json['created_at'] ?? '',
    );
  }

  // Method to convert an instance to JSON
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'status': status,
      'item_id': itemId,
      'item_name': itemName,
      'item_picture': itemPicture,
      'library_name': libraryName,
      'library_id': libraryId,
      'name': name,
      'description': description,
      'picture': picture,
      'created_at': createdAt,
    };
  }
}
