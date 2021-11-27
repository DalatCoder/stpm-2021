class Category {
  final int? id;
  final String? type;
  final String? name;
  final DateTime? createdAt;

  Category({
    this.id,
    this.type,
    this.name,
    this.createdAt,
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
