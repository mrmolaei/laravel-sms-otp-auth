import { useEffect, useState } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import axios from "axios";

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        mobile: '',
        otp: '',
    });

    const [mobileSent, setMobileSent] = useState(false);
    const [error, setError] = useState(null);

    const submit = async (e) => {
        e.preventDefault();

        if (mobileSent) {
            sendOtp()
        } else {
            sendMobile()
        }

        // post(route('otp.generate'));
    };

    async function sendMobile() {
        try {
            const response = await axios.post(route('auth.login.mobile'), data);
            // Handle success, e.g., display a success message or redirect
            setMobileSent(true)
            console.log('Success:', response.data);
            return true;
        } catch (error) {
            // Handle error, e.g., display error message
            setError(error.response.data.message);
            console.error('Error:', error.response.data);
        }
    }

    async function sendOtp() {
        try {
            const response = await axios.post(route('auth.login.web.otp'), data);
            if (response.data.success) {
                return post(route('login.inertia'));
            }
        } catch (err) {
            setError(err.response.data.message);
            console.error('Error:', err.response.data);
        }
    }

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit}>
                {mobileSent ?
                    <div>
                        <InputLabel htmlFor="otp" value="OTP"/>

                        <TextInput
                            id="otp"
                            type="text"
                            name="otp"
                            value={data.otp}
                            className="mt-1 block w-full"
                            autoComplete="off"
                            isFocused={true}
                            onChange={(e) => setData('otp', e.target.value)}
                        />

                        <InputError message={error} className="mt-2"/>
                    </div>
                    :
                    <div>
                        <InputLabel htmlFor="mobile" value="Mobile"/>

                        <TextInput
                            id="mobile"
                            type="text"
                            name="mobile"
                            value={data.mobile}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            isFocused={true}
                            onChange={(e) => setData('mobile', e.target.value)}
                        />

                        <InputError message={error} className="mt-2"/>
                    </div>
                }

                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            Forgot your password?
                        </Link>
                    )}

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
