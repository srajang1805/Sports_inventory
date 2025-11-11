import React, { useState, useEffect } from 'react';
import API from '../api';
import '../App.css';

export default function IssueModal({ onClose, onDone }) {
  const [items, setItems] = useState([]);
  const [selectedItem, setSelectedItem] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  // Load available items
  useEffect(() => {
    async function fetchItems() {
      try {
        const res = await API.get('/api/items.php');
        setItems(res.data);
      } catch (err) {
        setError('Unable to fetch items.');
      }
    }
    fetchItems();
  }, []);

  // Handle issue
  async function handleIssue(e) {
    e.preventDefault();
    if (!selectedItem || quantity <= 0) {
      setError('Please select an item and valid quantity.');
      return;
    }

    setLoading(true);
    try {
      const res = await API.post('/api/issues.php', {
        itemId: selectedItem,
        quantityIssued: quantity
      });
      if (res.data.error) {
        setError(res.data.error);
      } else {
        onDone(); // refresh dashboard
        onClose(); // close modal
      }
    } catch (err) {
      setError('Error issuing item.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="modal-backdrop">
      <div className="modal-content">
        <h2>🎾 Issue Equipment</h2>
        <form onSubmit={handleIssue}>
          <label>Select Item</label><br />
          <select
            value={selectedItem}
            onChange={(e) => setSelectedItem(e.target.value)}
            required
          >
            <option value="">-- Select Equipment --</option>
            {items.map((item) => (
              <option key={item.item_id} value={item.item_id}>
                {item.item_name} ({item.quantity} available)
              </option>
            ))}
          </select><br />

          <label>Quantity</label><br />
          <input
            type="number"
            value={quantity}
            min="1"
            onChange={(e) => setQuantity(e.target.value)}
          /><br />

          {error && <p style={{ color: '#ff4444' }}>{error}</p>}

          <button type="submit" disabled={loading}>
            {loading ? 'Issuing...' : '✅ Issue'}
          </button>
          <button
            type="button"
            onClick={onClose}
            style={{ backgroundColor: '#555', marginLeft: '10px' }}
          >
            ❌ Cancel
          </button>
        </form>
      </div>
    </div>
  );
}
