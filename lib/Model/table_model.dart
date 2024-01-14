class TableModel {
  int id;
  String tableNo;

  // constructor
  TableModel({
    required this.id,
    required this.tableNo
  });

  // factory method to create an equipment from a map
  factory TableModel.fromJson(Map<String, dynamic> json) {
    return TableModel(
        id: json['id'] ?? '',
        tableNo: json['table_no'] ?? ''
    );
  }

  // convert the equipment instance to a map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'table_no': tableNo
    };
  }
}