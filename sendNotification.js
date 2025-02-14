const admin = require("firebase-admin");
const express = require("express");
const bodyParser = require("body-parser");

// Initialize Firebase Admin SDK
const serviceAccount = require("./path/to/serviceAccountKey.json");
admin.initializeApp({
  credential: admin.credential.cert(serviceAccount)
});

const app = express();
app.use(bodyParser.json());

// Store tokens (for demonstration purposes, use a database in production)
let userTokens = [];

// Endpoint to save tokens
app.post("/subscribe", (req, res) => {
  const { token } = req.body;
  if (!userTokens.includes(token)) {
    userTokens.push(token);
  }
  res.status(200).send("Subscribed successfully.");
});

// Endpoint to send notifications
app.post("/send-notification", async (req, res) => {
  const { title, body, icon } = req.body;

  const message = {
    notification: { title, body, icon },
    tokens: userTokens // Multicast to all subscribed tokens
  };

  try {
    const response = await admin.messaging().sendMulticast(message);
    res.status(200).send(`Notifications sent: ${response.successCount}`);
  } catch (error) {
    console.error("Error sending notifications:", error);
    res.status(500).send("Failed to send notifications.");
  }
});

// Start the server
app.listen(3000, () => {
  console.log("Server running on http://localhost:3000");
});
