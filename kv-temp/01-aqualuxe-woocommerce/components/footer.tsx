import Link from "next/link"
import { Fish, Facebook, Instagram, Twitter, Youtube, Mail, Phone, MapPin } from "lucide-react"
import { Button } from "@/components/ui/button"

export default function Footer() {
  return (
    <footer className="bg-gray-900 text-white">
      <div className="container mx-auto px-4 py-12">
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* Company Info */}
          <div className="space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-teal-600 rounded-full flex items-center justify-center">
                <Fish className="h-6 w-6 text-white" />
              </div>
              <div>
                <h3 className="text-xl font-bold">AquaLuxe</h3>
                <p className="text-sm text-gray-400">Premium Ornamental Fish</p>
              </div>
            </div>
            <p className="text-gray-400 text-sm">
              Your trusted partner for premium ornamental fish and aquarium supplies. Bringing the beauty of aquatic
              life to enthusiasts worldwide.
            </p>
            <div className="flex space-x-4">
              <Button variant="ghost" size="icon" className="text-gray-400 hover:text-white">
                <Facebook className="h-5 w-5" />
              </Button>
              <Button variant="ghost" size="icon" className="text-gray-400 hover:text-white">
                <Instagram className="h-5 w-5" />
              </Button>
              <Button variant="ghost" size="icon" className="text-gray-400 hover:text-white">
                <Twitter className="h-5 w-5" />
              </Button>
              <Button variant="ghost" size="icon" className="text-gray-400 hover:text-white">
                <Youtube className="h-5 w-5" />
              </Button>
            </div>
          </div>

          {/* Quick Links */}
          <div className="space-y-4">
            <h4 className="text-lg font-semibold">Quick Links</h4>
            <div className="space-y-2">
              <Link href="/about" className="block text-gray-400 hover:text-white transition-colors">
                About Us
              </Link>
              <Link href="/fish-categories" className="block text-gray-400 hover:text-white transition-colors">
                Fish Categories
              </Link>
              <Link href="/care-guide" className="block text-gray-400 hover:text-white transition-colors">
                Care Guide
              </Link>
              <Link href="/shipping" className="block text-gray-400 hover:text-white transition-colors">
                Shipping Info
              </Link>
              <Link href="/returns" className="block text-gray-400 hover:text-white transition-colors">
                Returns & Exchanges
              </Link>
            </div>
          </div>

          {/* Customer Service */}
          <div className="space-y-4">
            <h4 className="text-lg font-semibold">Customer Service</h4>
            <div className="space-y-2">
              <Link href="/contact" className="block text-gray-400 hover:text-white transition-colors">
                Contact Us
              </Link>
              <Link href="/faq" className="block text-gray-400 hover:text-white transition-colors">
                FAQ
              </Link>
              <Link href="/support" className="block text-gray-400 hover:text-white transition-colors">
                Support Center
              </Link>
              <Link href="/track-order" className="block text-gray-400 hover:text-white transition-colors">
                Track Your Order
              </Link>
              <Link href="/warranty" className="block text-gray-400 hover:text-white transition-colors">
                Live Arrival Guarantee
              </Link>
            </div>
          </div>

          {/* Contact Info */}
          <div className="space-y-4">
            <h4 className="text-lg font-semibold">Contact Info</h4>
            <div className="space-y-3">
              <div className="flex items-center gap-3">
                <MapPin className="h-5 w-5 text-blue-400" />
                <div className="text-sm text-gray-400">
                  <p>123 Aquarium Street</p>
                  <p>Fish City, FC 12345</p>
                </div>
              </div>
              <div className="flex items-center gap-3">
                <Phone className="h-5 w-5 text-blue-400" />
                <span className="text-sm text-gray-400">+1 (555) 123-4567</span>
              </div>
              <div className="flex items-center gap-3">
                <Mail className="h-5 w-5 text-blue-400" />
                <span className="text-sm text-gray-400">info@aqualuxe.com</span>
              </div>
            </div>
            <div className="bg-gray-800 p-4 rounded-lg">
              <p className="text-sm font-medium mb-2">Business Hours</p>
              <p className="text-xs text-gray-400">Mon - Fri: 9:00 AM - 6:00 PM</p>
              <p className="text-xs text-gray-400">Sat - Sun: 10:00 AM - 4:00 PM</p>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-12 pt-8">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <p className="text-sm text-gray-400">© 2024 AquaLuxe. All rights reserved.</p>
            <div className="flex space-x-6 mt-4 md:mt-0">
              <Link href="/privacy" className="text-sm text-gray-400 hover:text-white transition-colors">
                Privacy Policy
              </Link>
              <Link href="/terms" className="text-sm text-gray-400 hover:text-white transition-colors">
                Terms of Service
              </Link>
              <Link href="/cookies" className="text-sm text-gray-400 hover:text-white transition-colors">
                Cookie Policy
              </Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  )
}
