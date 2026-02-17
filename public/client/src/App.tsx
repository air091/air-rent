import { createBrowserRouter } from "react-router-dom";
import Login from "./pages/public/Login";

const App = createBrowserRouter([
  {
    path: "/login",
    element: <Login />,
  },
]);

export default App;
