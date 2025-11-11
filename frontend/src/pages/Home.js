import React, { useEffect, useState } from 'react';
import Navbar from '../components/Navbar';
import InventoryTable from '../components/InventoryTable';
import IssueModal from '../components/IssueModal';
import API from '../api';
import '../App.css';

export default function Home() {
  const [items, setItems] = useState([]);
  const [issues, setIssues] = useState([]);
  const [showModal, setShowModal] = useState(false);

  async function fetchItems() {
    const res = await API.get('/api/items.php');
    setItems(res.data);
  }

  async function fetchIssues() {
    const res = await API.get('/api/issues.php');
    setIssues(res.data);
  }

  useEffect(() => {
    fetchItems();
    fetchIssues();
  }, []);

  return (
    <>
      <Navbar />
      <div className="container">
        <h1>🏅 Sports Equipment Dashboard</h1>
        <button onClick={() => setShowModal(true)}>➕ Issue Equipment</button>

        <InventoryTable items={items} />

        <h2>📋 Issued Items</h2>
        <div className="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {issues.length > 0 ? (
                issues.map((i) => (
                  <tr key={i.issue_id}>
                    <td>{i.issue_id}</td>
                    <td>{i.Item?.item_name || '—'}</td>
                    <td>{i.quantity_issued}</td>
                    <td>{i.status}</td>
                    <td>
                      {i.status === 'issued' && (
                        <button
                          className="return-btn"
                          onClick={() => {
                            API.post(`/api/issues_return.php`, { id: i.issue_id })
                              .then(fetchIssues)
                              .then(fetchItems);
                          }}
                        >
                          Return
                        </button>
                      )}
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="5" className="center">No issued items 🏸</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <IssueModal
          onClose={() => setShowModal(false)}
          onDone={() => {
            setShowModal(false);
            fetchItems();
            fetchIssues();
          }}
        />
      )}
    </>
  );
}
