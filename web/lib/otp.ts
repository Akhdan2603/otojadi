
export async function sendOTP(phone: string, otp: string) {
  const accountSid = process.env.TWILIO_ACCOUNT_SID;
  const authToken = process.env.TWILIO_AUTH_TOKEN;
  const fromNumber = process.env.TWILIO_PHONE_NUMBER;

  if (!accountSid || !authToken || !fromNumber) {
    console.warn("Twilio credentials not found. Logging OTP to console.");
    console.log(`OTP for ${phone}: ${otp}`);
    return true; // Simulate success
  }

  try {
    const client = require('twilio')(accountSid, authToken);
    await client.messages.create({
        body: `Your Otojadi verification code is: ${otp}`,
        from: fromNumber, // Or WhatsApp 'whatsapp:+14155238886'
        to: phone // Or 'whatsapp:' + phone
    });
    return true;
  } catch (error) {
    console.error("Failed to send OTP via Twilio:", error);
    return false;
  }
}
