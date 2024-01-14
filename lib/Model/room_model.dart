class RoomModel {
  String roomNo;
  String type;
  String picture;

  // constructor
  RoomModel({
    required this.roomNo,
    required this.type,
    required this.picture
  });

  // factory method to create an equipment from a map
  factory RoomModel.fromJson(Map<String, dynamic> json) {
    return RoomModel(
        roomNo: json['room_no'] ?? '',
        type: json['type'] ?? '',
        picture: json['picture'] ?? ''
    );
  }

  // convert the equipment instance to a map
  Map<String, dynamic> toJson() {
    return {
      'room_no': roomNo,
      'type': type,
      'picture': picture,
    };
  }
}