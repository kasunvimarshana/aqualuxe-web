import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Card, CardContent } from "@/components/ui/card"
import { Mail } from "lucide-react"

export function Newsletter() {
  return (
    <section className="py-16 bg-gradient-to-r from-blue-600 to-teal-500">
      <div className="container px-4">
        <Card className="max-w-2xl mx-auto border-0 shadow-2xl">
          <CardContent className="p-8 text-center">
            <div className="mb-6">
              <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-teal-500 rounded-full mb-4">
                <Mail className="h-8 w-8 text-white" />
              </div>
              <h2 className="text-2xl md:text-3xl font-bold mb-4">
                Stay Updated with{" "}
                <span className="bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent">
                  AquaLuxe
                </span>
              </h2>
              <p className="text-gray-600">
                Get the latest updates on new arrivals, exclusive offers, and expert aquarium care tips
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
              <Input type="email" placeholder="Enter your email address" className="flex-1" />
              <Button className="bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-700 hover:to-teal-600">
                Subscribe
              </Button>
            </div>

            <p className="text-xs text-gray-500 mt-4">
              By subscribing, you agree to our Privacy Policy and Terms of Service
            </p>
          </CardContent>
        </Card>
      </div>
    </section>
  )
}
