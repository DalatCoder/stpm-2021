class Category {
  final int id;
  final String type;
  final String name;
  final DateTime createdAt;

  Category({
    required this.id,
    required this.type,
    required this.name,
    required this.createdAt,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: int.parse(json["id"]),
      type: json["type"],
      name: json["name"],
      createdAt: DateTime.parse(json["created_at"]),
    );
  }
}
