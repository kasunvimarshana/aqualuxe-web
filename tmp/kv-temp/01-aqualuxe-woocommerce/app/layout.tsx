import type React from "react"
import type { Metadata } from "next"
import { Inter } from "next/font/google"
import "./globals.css"
import Header from "@/components/header"
import Footer from "@/components/footer"

const inter = Inter({ subsets: ["latin"] })

export const metadata: Metadata = {
  title: "AquaLuxe - Premium Ornamental Fish & Aquarium Supplies",
  description:
    "Discover the luxury of AquaLuxe - your trusted source for premium ornamental fish, koi, tropical fish, and aquarium supplies. Worldwide shipping with live arrival guarantee.",
  keywords: "ornamental fish, koi fish, tropical fish, aquarium supplies, premium fish, AquaLuxe",
    generator: 'v0.dev'
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body className={inter.className}>
        <Header />
        <main>{children}</main>
        <Footer />
      </body>
    </html>
  )
}
