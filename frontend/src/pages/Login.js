import React, { useState } from 'react';
import API from '../api';
import '../App.css';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  async function handleLogin(e) {
    e.preventDefault();
    try {
      const res = await API.post('/auth/login.php', { email, password });
      if (res.data.token) {
        localStorage.setItem('token', res.data.token);
        window.location.href = '/';
      } else {
        alert(res.data.error || 'Login failed');
      }
    } catch (err) {
      alert('Error connecting to server.');
    }
  }

  return (
    <div className="container">
      <h1>⚽ Sports Inventory Login</h1>
      <form onSubmit={handleLogin} className="center">
        <input
          type="email"
          placeholder="Enter Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        /><br />
        <input
          type="password"
          placeholder="Enter Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        /><br />
        <button type="submit">Login</button><br />
        <p>
          Don’t have an account? <a href="/register">Register</a>
        </p>
      </form>
    </div>
  );
}
