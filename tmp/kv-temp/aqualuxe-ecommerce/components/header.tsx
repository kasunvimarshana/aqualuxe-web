"use client"

import { useState } from "react"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet"
import { Menu, Search, ShoppingCart, User, Heart } from "lucide-react"

export function Header() {
  const [isOpen, setIsOpen] = useState(false)

  return (
    <header className="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/60">
      <div className="container flex h-16 items-center justify-between px-4">
        {/* Mobile Menu */}
        <Sheet open={isOpen} onOpenChange={setIsOpen}>
          <SheetTrigger asChild>
            <Button variant="ghost" size="icon" className="md:hidden">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Toggle menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" className="w-[300px] sm:w-[400px]">
            <nav className="flex flex-col gap-4">
              <Link href="/" className="text-lg font-semibold text-blue-900">
                AquaLuxe
              </Link>
              <Link href="/shop" className="text-sm font-medium hover:text-blue-600">
                Shop All
              </Link>
              <Link href="/tropical" className="text-sm font-medium hover:text-blue-600">
                Tropical Fish
              </Link>
              <Link href="/goldfish" className="text-sm font-medium hover:text-blue-600">
                Goldfish
              </Link>
              <Link href="/koi" className="text-sm font-medium hover:text-blue-600">
                Koi Fish
              </Link>
              <Link href="/accessories" className="text-sm font-medium hover:text-blue-600">
                Accessories
              </Link>
              <Link href="/about" className="text-sm font-medium hover:text-blue-600">
                About
              </Link>
              <Link href="/contact" className="text-sm font-medium hover:text-blue-600">
                Contact
              </Link>
            </nav>
          </SheetContent>
        </Sheet>

        {/* Logo */}
        <Link href="/" className="flex items-center space-x-2">
          <div className="h-8 w-8 rounded-full bg-gradient-to-r from-blue-600 to-teal-500 flex items-center justify-center">
            <span className="text-white font-bold text-sm">AL</span>
          </div>
          <span className="text-xl font-bold bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">
            AquaLuxe
          </span>
        </Link>

        {/* Desktop Navigation */}
        <nav className="hidden md:flex items-center space-x-6">
          <Link href="/shop" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Shop All
          </Link>
          <Link href="/tropical" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Tropical Fish
          </Link>
          <Link href="/goldfish" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Goldfish
          </Link>
          <Link href="/koi" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Koi Fish
          </Link>
          <Link href="/accessories" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Accessories
          </Link>
          <Link href="/about" className="text-sm font-medium hover:text-blue-600 transition-colors">
            About
          </Link>
          <Link href="/contact" className="text-sm font-medium hover:text-blue-600 transition-colors">
            Contact
          </Link>
        </nav>

        {/* Actions */}
        <div className="flex items-center space-x-2">
          <Button variant="ghost" size="icon" className="hidden sm:flex">
            <Search className="h-4 w-4" />
            <span className="sr-only">Search</span>
          </Button>
          <Button variant="ghost" size="icon">
            <Heart className="h-4 w-4" />
            <span className="sr-only">Wishlist</span>
          </Button>
          <Button variant="ghost" size="icon">
            <User className="h-4 w-4" />
            <span className="sr-only">Account</span>
          </Button>
          <Button variant="ghost" size="icon" className="relative">
            <ShoppingCart className="h-4 w-4" />
            <Badge className="absolute -top-2 -right-2 h-5 w-5 rounded-full p-0 text-xs bg-blue-600">3</Badge>
            <span className="sr-only">Cart</span>
          </Button>
        </div>
      </div>
    </header>
  )
}
