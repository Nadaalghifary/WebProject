const express = require('express');
const { OAuth2Client } = require('google-auth-library');
const app = express();
const client = new OAuth2Client('566360220617-fgq8p39jnvrb5g8h6ml5i75cqhhd7hmf.apps.googleusercontent.com');  // Replace with your Google Client ID

// Middleware to parse JSON bodies
app.use(express.json());

// Endpoint to verify the token
app.post('/verify-token', async (req, res) => {
    const token = req.body.id_token;
    
    try {
        // Verify the ID token
        const ticket = await client.verifyIdToken({
            idToken: token,
            audience: '566360220617-fgq8p39jnvrb5g8h6ml5i75cqhhd7hmf.apps.googleusercontent.com',  // Replace with your Google Client ID
        });

        const payload = ticket.getPayload();
        const userId = payload['sub']; // Unique user ID from Google

        // Return user information
        res.json({ message: 'Token verified successfully', userId: userId, name: payload.name, email: payload.email });
    } catch (error) {
        console.error('Error verifying ID token:', error);
        res.status(400).json({ error: 'Invalid token' });
    }
});

// Start the server
app.listen(3000, () => {
    console.log('Server started on http://localhost:3000');
});
