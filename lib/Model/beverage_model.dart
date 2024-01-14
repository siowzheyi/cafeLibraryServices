class BeverageModel {
  String name;
  String category;
  String price;
  String picture;

  // constructor
  BeverageModel({
    required this.name,
    required this.category,
    required this.price,
    required this.picture
  });

  // factory method to create a beverage from a map
  factory BeverageModel.fromJson(Map<String, dynamic> json) {
    return BeverageModel(
        name: json['name'] ?? '',
        category: json['category'] ?? '',
        price: json['price'] ?? '',
        picture: json['picture'] ?? ''
    );
  }

  // convert the beverage instance to a map
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'category': category,
      'price': price,
      'picture': picture,
    };
  }
}