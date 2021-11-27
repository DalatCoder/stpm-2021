import 'package:flutter/cupertino.dart';
import 'package:sffmobileapp/redux/state_objects/user_state.dart';

@immutable
class AppState {
  final UserState userState;

  const AppState({required this.userState});

  factory AppState.initial() {
    return AppState(userState: UserState.initial());
  }

  AppState copyWith({UserState? userState}) {
    return AppState(userState: userState ?? this.userState);
  }

  @override
  int get hashCode => userState.hashCode;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is AppState && userState == other.userState;
}
