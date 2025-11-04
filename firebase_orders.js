import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getFirestore, doc, setDoc } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyBCSfpkAlodK6sbKcmQaxf3oWChhT9_E4I",
    authDomain: "chick-chicken-249dc.firebaseapp.com",
    projectId: "chick-chicken-249dc",
    storageBucket: "chick-chicken-249dc.firebasestorage.app",
    messagingSenderId: "908299483427",
    appId: "1:908299483427:web:d3eae5daaaed08926d3496",
    measurementId: "G-TFLG5F7MVD"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

// --- Save Order Function ---
export async function addOrder(orderData) {
  // You can rename or customize this ID
  const orderId = orderData.item + "_" + Date.now();

  await setDoc(doc(db, "orders", orderId), {
    ...orderData,
    createdAt: new Date().toLocaleString(),
  });

  alert(`✅ Order placed for ${orderData.item}`);
}