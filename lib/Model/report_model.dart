class EquipmentReportModel {
  int equipmentId;
  String name;
  String description;
  String picture;

  // constructor
  EquipmentReportModel({
    required this.equipmentId,
    required this.name,
    required this.description,
    required this.picture,
  });

  // factory method to create a report from a map
  factory EquipmentReportModel.fromJson(Map<String, dynamic> json) {
    return EquipmentReportModel(
        equipmentId: json['equipment_id'] ?? '',
        name: json['name'] ?? '',
        description: json['description'] ?? '',
        picture: json['picture'] ?? '',
    );
  }

  // convert the report instance to a map
  Map<String, dynamic> toJson() {
    return {
      'equipment_id': equipmentId.toInt(),
      'name': name,
      'description': description,
      'picture': picture,
    };
  }
}

class RoomReportModel {
  int roomId;
  String name;
  String description;
  String picture;

  // constructor
  RoomReportModel({
    required this.roomId,
    required this.name,
    required this.description,
    required this.picture,
  });

  // factory method to create a room from a map
  factory RoomReportModel.fromJson(Map<String, dynamic> json) {
    return RoomReportModel(
      roomId: json['room_id'] ?? '',
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      picture: json['picture'] ?? '',
    );
  }

  // convert the room instance to a map
  Map<String, dynamic> toJson() {
    return {
      'room_id': roomId,
      'name': name,
      'description': description,
      'picture': picture,
    };
  }
}

class BookReportModel {
  int bookId;
  String name;
  String description;
  String picture;

  // constructor
  BookReportModel({
    required this.bookId,
    required this.name,
    required this.description,
    required this.picture,
  });

  // factory method to create a report from a map
  factory BookReportModel.fromJson(Map<String, dynamic> json) {
    return BookReportModel(
      bookId: json['book_id'] ?? '',
      name: json['name'] ?? '',
      description: json['description'] ?? '',
      picture: json['picture'] ?? '',
    );
  }

  // convert the report instance to a map
  Map<String, dynamic> toJson() {
    return {
      'book_id': bookId,
      'name': name,
      'description': description,
      'picture': picture,
    };
  }
}