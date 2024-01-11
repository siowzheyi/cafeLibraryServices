class EquipmentModel {
  String name;
  String picture;

  // constructor
  EquipmentModel({
    required this.name,
    required this.picture
  });

  // factory method to create an equipment from a map
  factory EquipmentModel.fromJson(Map<String, dynamic> json) {
    return EquipmentModel(
        name: json['name'] ?? '',
        picture: json['picture'] ?? ''
    );
  }

  // convert the equipment instance to a map
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'picture': picture,
    };
  }
}