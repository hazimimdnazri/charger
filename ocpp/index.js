import express from 'express'
import cors from 'cors'
import crypto from 'crypto'
import axios from 'axios'

const app = express()

let subscribed = false;
let intervalId = null;
let customerChargerId = null;

app.use(cors())
app.use(express.json())

app.get('/', (req, res) => {
    res.send('The server is up and running!')
})

app.get('/health', (req, res) => {
    res.status(200).json({
        status: 'ok',
        message: 'The server is up and running!'
    })
})

app.post('/start', (req, res) => {
    const payload = req.body
    payload.transaction = 'start_charging'
    payload.status = 'charging'
    payload.soc_percent = 10

    customerChargerId = req.body.customer_charger_id
    sendResponse(payload)

    setTimeout(() => {
        res.status(200).json({
            status: 'OK',
            message: 'Charging initiated!'
        })
    }, 5000)
})

app.post('/stop', (req, res) => {
    const payload = req.body
    payload.transaction = 'stop_charging'
    payload.status = 'idle'
    payload.soc_percent = 10

    customerChargerId = null
    sendResponse(payload)

    setTimeout(async () => {
        res.status(200).json({
            status: 'OK',
            message: 'Charging stopped!'
        })
    }, 5000)
})

function startHeartbeat() {
    if (subscribed && !intervalId) {
        intervalId = setInterval(() => {
            console.log('Charging updated!')
            sendUpdate({
                customer_charger_id: customerChargerId,
                transaction: 'update_charging',
                soc_percent: 2
            })
        }, 10000)
    }
}

async function sendResponse(payload) {
    const signature = signHmacSha256('hazimimdnazri', JSON.stringify(payload))
    const response = await axios.post('http://api:8000/api/callback', payload, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Signature': signature
        }
    })

    if (response.status === 200) {
        if (payload.transaction === 'start_charging') {
            subscribed = true;
            startHeartbeat()
        } else {
            subscribed = false;
            stopHeartbeat()
        }
    }
}

function sendUpdate(payload) {
    if (subscribed) {
        const signature = signHmacSha256('hazimimdnazri', JSON.stringify(payload))
        axios.post('http://api:8000/api/callback', payload, {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Signature': signature
            }
        })
    }
}

function stopHeartbeat() {
    if (intervalId) {
        clearInterval(intervalId);
        intervalId = null;
    }
}

function signHmacSha256(key, str) {
    let hmac = crypto.createHmac("sha256", key);
    let signed = hmac.update(Buffer.from(str, 'utf-8')).digest("hex");
    return signed
}

app.listen(3000, () => {
    console.log(`Event started on port 3000...`)
});