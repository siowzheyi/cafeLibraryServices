import 'dart:convert';

BeverageModel beverageModelFromJson(String str) =>
    BeverageModel.fromJson(json.decode(str));

String beverageModelToJson(BeverageModel data) => json.encode(data.toJson());

class BeverageModel {
  BeverageModel({
    required this.image,
    required this.id,
    required this.name,
    required this.price,
    required this.description,
    required this.status,
  });

  String image;
  String id;
  String name;
  //double price;
  String price;
  String description;
  String status;

  factory BeverageModel.fromJson(Map<String, dynamic> json) => BeverageModel(
    id: json["id"],
    name: json["name"],
    description: json["description"],
    image: json["image"],
    //price: double.parse(json["price"].toString()),
    price: json["price"],
    status: json["status"],
  );

  Map<String, dynamic> toJson() => {
    "id": id,
    "name": name,
    "description": description,
    "image": image,
    "price": price,
    "status": status,
  };
}