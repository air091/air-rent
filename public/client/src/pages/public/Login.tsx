import { useState } from "react";
import type { IAccountCredentials } from "../../interfaces/userInterface";

export default function Login() {
  const [user, setUser] = useState<IAccountCredentials>({
    email: "",
    password: "",
  });

  async function loginAPI(email: string, password: string) {
    const response = await fetch("http://localhost:8888/api/auth/login", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
      credentials: "include",
    });
    const data = await response.json();
    console.log(data);
  }

  const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = event.target;
    setUser((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
    event.preventDefault();
    await loginAPI(user.email, user.password);
  };

  return (
    <div className="w-full h-screen flex justify-center items-center">
      <div className="border w-full max-w-72 px-6 py-8 rounded-md">
        <header>
          <h1 className="font-semibold text-2xl text-center mb-3">
            Login your Account
          </h1>
        </header>
        <form className="text-md" onSubmit={handleSubmit}>
          <div>
            <label htmlFor="email">Email</label>
            <input
              id="email"
              type="email"
              name="email"
              onChange={handleChange}
              value={user.email || ""}
              placeholder="ex. johndoe@email.com"
              className="block border rounded-sm py-1 px-2 w-full my-1"
            />
          </div>
          <div>
            <label htmlFor="password">Password</label>
            <input
              id="password"
              type="password"
              name="password"
              placeholder="Enter password"
              onChange={handleChange}
              value={user.password || ""}
              className="block border rounded-sm py-1 px-2 w-full my-1"
            />
          </div>
          <div className="text-center">
            <button className="border px-6 py-1 cursor-pointer rounded-sm mt-1">
              Login
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
