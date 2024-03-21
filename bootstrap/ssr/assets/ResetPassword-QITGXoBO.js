import { jsxs, jsx } from "react/jsx-runtime";
import { useEffect } from "react";
import { G as Guest } from "./GuestLayout-C6RG6yLW.js";
import { T as TextInput, I as InputError } from "./TextInput-bv6IrSoZ.js";
import { I as InputLabel } from "./InputLabel-CaoVq05r.js";
import { P as PrimaryButton } from "./PrimaryButton-C5ts3FGL.js";
import { useForm, Head } from "@inertiajs/react";
import "./ApplicationLogo-VXSMMN2A.js";
function ResetPassword({ token, email }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    token,
    email,
    password: "",
    password_confirmation: ""
  });
  useEffect(() => {
    return () => {
      reset("password", "password_confirmation");
    };
  }, []);
  const submit = (e) => {
    e.preventDefault();
    post(route("password.store"));
  };
  return /* @__PURE__ */ jsxs(Guest, { children: [
    /* @__PURE__ */ jsx(Head, { title: "Reset Password" }),
    /* @__PURE__ */ jsxs("form", { onSubmit: submit, children: [
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx(InputLabel, { htmlFor: "email", value: "Email" }),
        /* @__PURE__ */ jsx(
          TextInput,
          {
            id: "email",
            type: "email",
            id: "email",
            name: "email",
            value: data.email,
            className: "mt-1 block w-full",
            autoComplete: "username",
            onChange: (e) => setData("email", e.target.value)
          }
        ),
        /* @__PURE__ */ jsx(InputError, { message: errors.email, className: "mt-2" })
      ] }),
      /* @__PURE__ */ jsxs("div", { className: "mt-4", children: [
        /* @__PURE__ */ jsx(InputLabel, { htmlFor: "password", value: "Password" }),
        /* @__PURE__ */ jsx(
          TextInput,
          {
            id: "password",
            type: "password",
            name: "password",
            value: data.password,
            className: "mt-1 block w-full",
            autoComplete: "new-password",
            isFocused: true,
            onChange: (e) => setData("password", e.target.value)
          }
        ),
        /* @__PURE__ */ jsx(InputError, { message: errors.password, className: "mt-2" })
      ] }),
      /* @__PURE__ */ jsxs("div", { className: "mt-4", children: [
        /* @__PURE__ */ jsx(InputLabel, { htmlFor: "password_confirmation", value: "Confirm Password" }),
        /* @__PURE__ */ jsx(
          TextInput,
          {
            type: "password",
            id: "password_confirmation",
            name: "password_confirmation",
            value: data.password_confirmation,
            className: "mt-1 block w-full",
            autoComplete: "new-password",
            onChange: (e) => setData("password_confirmation", e.target.value)
          }
        ),
        /* @__PURE__ */ jsx(InputError, { message: errors.password_confirmation, className: "mt-2" })
      ] }),
      /* @__PURE__ */ jsx("div", { className: "flex items-center justify-end mt-4", children: /* @__PURE__ */ jsx(PrimaryButton, { className: "ms-4", disabled: processing, children: "Reset Password" }) })
    ] })
  ] });
}
export {
  ResetPassword as default
};
