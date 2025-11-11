import React, { useState } from 'react';
import API from '../api';
import '../App.css';

export default function Register() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  async function handleRegister(e) {
    e.preventDefault();
    try {
      const res = await API.post('/auth/register.php', { name, email, password });
      if (res.data.token) {
        localStorage.setItem('token', res.data.token);
        window.location.href = '/';
      } else {
        alert(res.data.error || 'Registration failed');
      }
    } catch (err) {
      alert('Error connecting to server.');
    }
  }

  return (
    <div className="container">
      <h1>🏏 Create Account</h1>
      <form onSubmit={handleRegister} className="center">
        <input
          type="text"
          placeholder="Full Name"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
        /><br />
        <input
          type="email"
          placeholder="Email Address"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        /><br />
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        /><br />
        <button type="submit">Register</button><br />
        <p>
          Already have an account? <a href="/login">Login</a>
        </p>
      </form>
    </div>
  );
}
