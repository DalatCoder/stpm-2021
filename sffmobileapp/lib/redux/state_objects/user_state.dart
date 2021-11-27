import 'package:flutter/cupertino.dart';
import 'package:sffmobileapp/models/user.dart';

@immutable
class UserState {
  final bool? isLoading;
  final bool? loginError;
  final User? user;

  const UserState({
    this.isLoading,
    this.loginError,
    this.user,
  });

  factory UserState.initial() {
    return const UserState(isLoading: false, loginError: false, user: null);
  }

  UserState copyWith({bool? isLoading, bool? loginError, User? user}) {
    return UserState(
      isLoading: isLoading ?? this.isLoading,
      loginError: loginError ?? this.loginError,
      user: user ?? this.user,
    );
  }

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is UserState &&
          runtimeType == other.runtimeType &&
          isLoading == other.isLoading &&
          loginError == other.loginError &&
          user == other.user;

  @override
  int get hashCode => isLoading.hashCode ^ user.hashCode;
}
