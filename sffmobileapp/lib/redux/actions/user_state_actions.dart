import 'package:sffmobileapp/models/user.dart';
import 'package:sffmobileapp/redux/actions/action.dart';

class StartLoadingAction extends Action {
  StartLoadingAction();
}

class LoginSuccessAction {
  // Payload
  final User? user;

  LoginSuccessAction(this.user);
}

class LoginFailedAction {
  LoginFailedAction();
}
