class User {
  final int? id;
  final String? type;
  final String? username;
  final String? email;
  final String? password;
  final String? displayName;
  final String? avatar;
  final DateTime? createdAt;

  User({
    this.id,
    this.type,
    this.username,
    this.email,
    this.password,
    this.displayName,
    this.avatar,
    this.createdAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: int.parse(json["id"]),
      type: json["type"],
      username: json["username"],
      email: json["email"],
      password: json["password"],
      displayName: json["display_name"],
      avatar: json["avatar"],
      createdAt: DateTime.parse(json["created_at"]),
    );
  }
}
