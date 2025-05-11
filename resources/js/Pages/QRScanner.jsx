import React, { useState, useEffect, useRef } from 'react';
import { Html5QrcodeScanner, Html5QrcodeSupportedFormats } from 'html5-qrcode';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import axios from 'axios';

const QRScanner = ({ auth }) => {
    console.log('QRScanner auth prop:', auth);
    const [cart, setCart] = useState([]);
    const [scanning, setScanning] = useState(false);
    const [total, setTotal] = useState(0);
    const [error, setError] = useState(null);
    const scannerRef = useRef(null);

    useEffect(() => {
        // Check authentication status
        if (!auth?.user) {
            console.log('No auth user found, redirecting to login...');
            router.visit('/login');
            return;
        }
    }, [auth]);

    useEffect(() => {
        if (scanning) {
            try {
                // Check if camera is available
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('Camera access is not supported in your browser');
                }

                // Initialize scanner only if not already initialized
                if (!scannerRef.current) {
                    scannerRef.current = new Html5QrcodeScanner('reader', {
                        qrbox: {
                            width: 250,
                            height: 250,
                        },
                        fps: 10,
                        aspectRatio: 1.0,
                        showTorchButtonIfSupported: true,
                        showZoomSliderIfSupported: true,
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E,
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.CODE_93,
                            Html5QrcodeSupportedFormats.QR_CODE
                        ]
                    });

                    scannerRef.current.render(onScanSuccess, onScanError);
                }
            } catch (err) {
                setError(err.message);
                setScanning(false);
            }
        } else {
            // Clean up scanner when stopping
            if (scannerRef.current) {
                scannerRef.current.clear();
                scannerRef.current = null;
            }
        }

        return () => {
            if (scannerRef.current) {
                scannerRef.current.clear();
                scannerRef.current = null;
            }
        };
    }, [scanning]);

    const onScanSuccess = async (decodedText) => {
        try {
            const response = await axios.get(`/api/products/barcode/${decodedText}`);
            const product = response.data;
            
            setCart(prevCart => {
                const existingItem = prevCart.find(item => item.id === product.id);
                if (existingItem) {
                    return prevCart.map(item =>
                        item.id === product.id
                            ? { ...item, quantity: item.quantity + 1 }
                            : item
                    );
                }
                return [...prevCart, { ...product, quantity: 1 }];
            });
        } catch (error) {
            console.error('Error fetching product:', error);
            alert('Product not found!');
        }
    };

    const onScanError = (error) => {
        console.warn(`QR Code scan error: ${error}`);
        setError(error);
    };

    const updateQuantity = (productId, change) => {
        setCart(prevCart =>
            prevCart.map(item => {
                if (item.id === productId) {
                    const newQuantity = item.quantity + change;
                    return newQuantity > 0 ? { ...item, quantity: newQuantity } : item;
                }
                return item;
            }).filter(item => item.quantity > 0)
        );
    };

    const calculateTotal = () => {
        return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    };

    const handleCheckout = async () => {
        try {
            const response = await axios.post('/api/sales', {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                })),
                total_amount: calculateTotal(),
                payment_method: 'cash'
            });

            if (response.data.success) {
                alert('Sale completed successfully!');
                setCart([]);
            }
        } catch (error) {
            console.error('Error processing sale:', error);
            alert('Error processing sale. Please try again.');
        }
    };

    useEffect(() => {
        setTotal(calculateTotal());
    }, [cart]);

    // If auth is not available, show a loading state
    if (!auth?.user) {
        return (
            <div className="min-h-screen bg-gray-100 flex items-center justify-center">
                <div className="text-center">
                    <h2 className="text-xl font-semibold">Loading...</h2>
                </div>
            </div>
        );
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">QR Scanner</h2>}
        >
            <Head title="QR Scanner" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {error && (
                                <div className="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                    {error}
                                </div>
                            )}
                            
                            <div className="flex justify-between mb-4">
                                <button
                                    onClick={() => setScanning(!scanning)}
                                    className="bg-blue-500 hover:bg-blue-700 !text-black font-bold py-2 px-4 rounded"
                                >
                                    {scanning ? 'Stop Scanning' : 'Start Scanning'}
                                </button>
                                <button
                                    onClick={handleCheckout}
                                    disabled={cart.length === 0}
                                    className={`${
                                        cart.length === 0
                                            ? 'bg-gray-300'
                                            : 'bg-green-500 hover:bg-green-700'
                                    } !text-black font-bold py-2 px-4 rounded`}
                                >
                                    Checkout
                                </button>
                            </div>

                            {scanning && (
                                <div id="reader" className="mb-4"></div>
                            )}

                            <div className="mt-4">
                                <h3 className="text-lg font-semibold mb-2">Cart</h3>
                                {cart.length === 0 ? (
                                    <p>No items in cart</p>
                                ) : (
                                    <div className="space-y-2">
                                        {cart.map((item) => (
                                            <div key={item.id} className="flex justify-between items-center border-b pb-2">
                                                <div>
                                                    <p className="font-medium">{item.name}</p>
                                                    <p className="text-sm text-gray-600">${item.price}</p>
                                                </div>
                                                <div className="flex items-center space-x-2">
                                                    <button
                                                        onClick={() => updateQuantity(item.id, -1)}
                                                        className="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded"
                                                    >
                                                        -
                                                    </button>
                                                    <span>{item.quantity}</span>
                                                    <button
                                                        onClick={() => updateQuantity(item.id, 1)}
                                                        className="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded"
                                                    >
                                                        +
                                                    </button>
                                                </div>
                                            </div>
                                        ))}
                                        <div className="mt-4 text-right">
                                            <p className="text-lg font-bold">Total: ${total.toFixed(2)}</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
};

export default QRScanner; 