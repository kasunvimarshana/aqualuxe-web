"use client"

import { useState } from "react"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet"
import { Search, ShoppingCart, User, Menu, Fish, Heart, Phone, Mail } from "lucide-react"

export default function Header() {
  const [cartCount] = useState(3)

  return (
    <header className="w-full">
      {/* Top Bar */}
      <div className="bg-blue-900 text-white py-2 text-sm">
        <div className="container mx-auto px-4 flex justify-between items-center">
          <div className="flex items-center gap-6">
            <div className="flex items-center gap-2">
              <Phone className="h-4 w-4" />
              <span>+1 (555) 123-4567</span>
            </div>
            <div className="flex items-center gap-2">
              <Mail className="h-4 w-4" />
              <span>info@aqualuxe.com</span>
            </div>
          </div>
          <div className="hidden md:block">
            <span>Free shipping on orders over $200 worldwide!</span>
          </div>
        </div>
      </div>

      {/* Main Header */}
      <div className="bg-white shadow-lg">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-20">
            {/* Logo */}
            <Link href="/" className="flex items-center gap-3">
              <div className="w-12 h-12 bg-gradient-to-r from-blue-600 to-teal-600 rounded-full flex items-center justify-center">
                <Fish className="h-7 w-7 text-white" />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-gray-900">AquaLuxe</h1>
                <p className="text-xs text-gray-600">Premium Ornamental Fish</p>
              </div>
            </Link>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center space-x-8">
              <Link href="/" className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                Home
              </Link>
              <div className="relative group">
                <button className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                  Fish Categories
                </button>
                <div className="absolute top-full left-0 mt-2 w-48 bg-white shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                  <div className="py-2">
                    <Link href="/koi" className="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                      Koi Fish
                    </Link>
                    <Link
                      href="/tropical"
                      className="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600"
                    >
                      Tropical Fish
                    </Link>
                    <Link
                      href="/goldfish"
                      className="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600"
                    >
                      Goldfish
                    </Link>
                    <Link href="/betta" className="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                      Betta Fish
                    </Link>
                  </div>
                </div>
              </div>
              <Link href="/supplies" className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                Aquarium Supplies
              </Link>
              <Link href="/care-guide" className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                Care Guide
              </Link>
              <Link href="/about" className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                About
              </Link>
              <Link href="/contact" className="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                Contact
              </Link>
            </nav>

            {/* Search Bar */}
            <div className="hidden md:flex items-center flex-1 max-w-md mx-8">
              <div className="relative w-full">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
                <input
                  type="text"
                  placeholder="Search for fish, supplies..."
                  className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            {/* Action Buttons */}
            <div className="flex items-center gap-4">
              <Button variant="ghost" size="icon" className="hidden md:flex">
                <Heart className="h-5 w-5" />
              </Button>
              <Button variant="ghost" size="icon" className="hidden md:flex">
                <User className="h-5 w-5" />
              </Button>
              <Button variant="ghost" size="icon" className="relative">
                <ShoppingCart className="h-5 w-5" />
                {cartCount > 0 && (
                  <Badge className="absolute -top-2 -right-2 h-5 w-5 rounded-full p-0 flex items-center justify-center text-xs bg-blue-600">
                    {cartCount}
                  </Badge>
                )}
              </Button>

              {/* Mobile Menu */}
              <Sheet>
                <SheetTrigger asChild>
                  <Button variant="ghost" size="icon" className="lg:hidden">
                    <Menu className="h-6 w-6" />
                  </Button>
                </SheetTrigger>
                <SheetContent side="right" className="w-80">
                  <div className="flex flex-col space-y-6 mt-6">
                    <Link href="/" className="text-lg font-medium">
                      Home
                    </Link>
                    <div className="space-y-3">
                      <p className="text-lg font-medium">Fish Categories</p>
                      <div className="pl-4 space-y-2">
                        <Link href="/koi" className="block text-gray-600">
                          Koi Fish
                        </Link>
                        <Link href="/tropical" className="block text-gray-600">
                          Tropical Fish
                        </Link>
                        <Link href="/goldfish" className="block text-gray-600">
                          Goldfish
                        </Link>
                        <Link href="/betta" className="block text-gray-600">
                          Betta Fish
                        </Link>
                      </div>
                    </div>
                    <Link href="/supplies" className="text-lg font-medium">
                      Aquarium Supplies
                    </Link>
                    <Link href="/care-guide" className="text-lg font-medium">
                      Care Guide
                    </Link>
                    <Link href="/about" className="text-lg font-medium">
                      About
                    </Link>
                    <Link href="/contact" className="text-lg font-medium">
                      Contact
                    </Link>
                  </div>
                </SheetContent>
              </Sheet>
            </div>
          </div>
        </div>
      </div>
    </header>
  )
}
