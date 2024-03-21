import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import axios from "axios";

export default function Login({ otp, user_id }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        user_id: user_id,
        otp: '',
    });

    const submit = async (e) => {
        e.preventDefault();

        try {
            const response = await axios.post(route('otp.login'), { mobile });
            // Handle success, e.g., display a success message or redirect
            console.log('Success:', response.data);
        } catch (error) {
            // Handle error, e.g., display error message
            //setError(error.response.data.message);
            console.error('Error:', error);
        }

        //post(route('otp.getlogin'));
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <p>Your OTP is: {otp}</p>
            <p>Your user ID: {user_id}</p>

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="otp" value="OTP" />

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

                    <InputError message={errors.otp} className="mt-2" />
                </div>

                <div className="flex items-center justify-end mt-4">

                    <PrimaryButton className="ms-4" disabled={processing}>
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
